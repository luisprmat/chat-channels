<?php

use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public $channel;

    public $messages;

    public $subscribed;

    public function mount(Channel $channel): void
    {
        $this->channel = $channel;
        $this->messages = $this->channel->getMessages()->toArray();
        $this->subscribed = $this->channel->isSubscribed(Auth::user());
    }

    public function join(): void
    {
        $this->subscribed = $this->channel->subscribe(Auth::user());
    }

    public function send(string $message): void
    {
        $this->channel->send(Auth::user(), $message);
        $this->messages = $this->channel->getMessages()->toArray();
    }
};
?>

<div x-data="channel" class="flex h-[calc(100vh-100px)] w-full flex-col justify-between p-4 pb-2">
    <div class="messages mb-4 flex h-full grow flex-col overflow-y-scroll" x-ref="messages">
        <span @class([
            'mt-auto w-full py-4 text-center text-lg',
            'mb-4 border-b' => count($messages) > 0,
        ])>
            {!! __('This is the very beginning of the :channel channel.', ['channel' => '<strong>'.e($channel->name).'</strong>']) !!}
        </span>

        <template x-for="message in $wire.messages">
            <div class="flex gap-x-2">
                <img :src="message.user.avatar" :alt="message.user.name" class="size-10 rounded-md" />

                <div>
                    <div class="flex items-center gap-x-2">
                        <span class="text-lg font-bold" x-text="message.user.name"></span>

                        <time class="text-sm text-gray-600" x-text="message.sent_at"></time>
                    </div>

                    <div x-html="message.content" class="text-lg"></div>
                </div>
            </div>
        </template>
    </div>

    <div class="flex w-full" @submitted.stop="send($event.detail.message)">
        @if ($subscribed)
            <div class="flex w-full flex-col gap-y-1">
                <x-editor channel="{{ $channel->name }}" />

                <!-- Typing Indicator -->
                <span class="block shrink-0 text-xs text-gray-500 after:content-['\200b']"></span>
            </div>
        @else
            <div class="flex grow flex-col items-center justify-center gap-y-4 rounded-md border bg-gray-100 p-6">
                <span class="text-lg font-bold"> #{{ $channel->name }} </span>

                <button type="submit" class="rounded-md bg-green-800 px-4 py-2 text-base text-white" wire:click="join">
                    {{ __('Join channel') }}
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    Alpine.data('channel', () => {
        return {
            isTyping: false,

            usersTyping: [],

            channel: null,

            init() {
                this.scrollPosition();
            },

            send(message) {
                this.$wire.send(message);
            },

            scrollPosition() {
                this.$watch('$wire.messages', () => {
                    this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
                });
            },
        };
    });
</script>
