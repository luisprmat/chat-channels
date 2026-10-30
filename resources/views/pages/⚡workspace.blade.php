<?php

use App\Models\Channel;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

new class extends Component
{
    public $channels;

    public $channel;

    public $users;

    public $subscribers;

    public function mount(Channel $channel): void
    {
        $this->channels = Channel::all();
        $this->channel = $channel;
        $this->users = User::orderBy('name')->get();
        $this->subscribers = $this->channel->getSubscribers();
    }

    public function render(): View
    {
        return $this->view()
            ->layout('layouts::chat')
            ->title($this->channel->name);
    }
};
?>

<div class="flex h-[calc(100vh-40px)] w-full rounded-lg bg-fuchsia-800">
    <!-- Channels -->
    <div class="bg-sidebar shrink-0 overflow-y-scroll rounded-l-lg">
        <div class="flex h-14 items-center gap-x-32 border-b p-4">
            <h1 class="text-xl font-bold">Laravel</h1>

            <x-icons.pencil class="size-6 text-gray-500" />
        </div>

        <div class="flex flex-col gap-y-6 p-2 text-lg text-gray-700">
            <ul class="flex flex-col gap-y-3">
                <li class="flex items-center gap-x-2 rounded-md px-4 py-1">
                    <x-icons.message class="size-6 text-gray-700" />

                    Threads
                </li>

                <li class="flex items-center gap-x-2 rounded-md px-4 py-1">
                    <x-icons.airplane class="size-6 text-gray-700" />

                    Drafts & sent
                </li>
            </ul>

            <ul class="flex flex-col gap-y-2" x-data="{ open: true }">
                <li>
                    <button
                        @click="open = ! open"
                        class="flex w-full items-center gap-x-2 rounded-md px-4 py-1 hover:bg-white/30"
                    >
                        <x-icons.chevron-down class="size-3 text-gray-700" />

                        Channels
                    </button>
                </li>

                <li>
                    <ul x-cloak x-show="open">
                        @foreach ($channels as $sidebar)
                            <li>
                                <a
                                    href="{{ route("workspace", $sidebar) }}"
                                    class="{{ $channel->name === $sidebar->name ? "bg-fuchsia-900 text-white" : "" }} flex items-center gap-x-2 rounded-md px-4 py-1 hover:bg-fuchsia-900 hover:text-white"
                                    wire:navigate
                                >
                                    <x-icons.hashtag class="size-4 text-inherit" />

                                    {{ $sidebar->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <ul class="flex flex-col gap-y-2" x-data="{ open: true }">
                <li>
                    <button
                        @click="open = ! open"
                        class="flex w-full items-center gap-x-2 rounded-md px-4 py-1 hover:bg-white/30"
                    >
                        <x-icons.chevron-down class="size-3 text-gray-700" />

                        Direct messages
                    </button>
                </li>

                <li>
                    <x-users :$users />
                </li>
            </ul>
        </div>
    </div>

    <div class="flex w-full flex-col rounded-r-lg bg-white">
        <div class="flex h-14 items-center justify-between border-b p-3">
            <h1 class="text-xl font-bold"># {{ $channel->name }}</h1>

            <div class="flex rounded-sm border p-1" wire:ignore>
                <div class="flex items-center -space-x-1">
                    @foreach ($subscribers->take(3) as $subscriber)
                        <img
                            src="{{ $subscriber->avatar }}"
                            alt="{{ $subscriber->name }}"
                            class="size-5 rounded-md border border-white"
                        />
                    @endforeach
                </div>

                <span class="px-2">{{ $subscribers->count() }}</span>
            </div>
        </div>

        <!-- Channel -->
        <livewire:channel :channel="$channel" />
    </div>
</div>
