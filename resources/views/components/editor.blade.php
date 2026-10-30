<div
    class="w-full rounded-md border border-gray-400 shadow-md"
    x-data="{
        message: '',
        submit() {
            this.$dispatch('submitted', { message: this.message });
            this.message = '';
        },
    }"
>
    <div class="flex rounded-t-md bg-gray-100 p-2">
        <button>
            <x-icons.smile class="size-6 text-gray-400" />
        </button>
    </div>

    <input
        x-model="message"
        type="text"
        class="h-12 w-full resize-none border-none p-3 text-gray-700 focus:outline-hidden"
        name="message"
        placeholder="{{ __('Message #:channel', ['channel' => $channel]) }}"
        autofocus
        @keydown.enter="submit"
        @keyup="$dispatch('typing')"
    />

    <div class="flex justify-end p-2">
        <button @click="submit()">
            <x-icons.airplane class="size-6 text-gray-400" />
        </button>
    </div>
</div>
