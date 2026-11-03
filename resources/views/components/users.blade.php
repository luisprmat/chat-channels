@props(['users'])

<ul x-cloak x-data="users" x-show="open">
    <template x-for="user in users">
        <li>
            <span class="flex items-center gap-x-2 rounded-md px-4 py-1 hover:bg-fuchsia-900 hover:text-white">
                <span class="relative">
                    <img :src="user.avatar" :alt="user.name" class="size-6 rounded-md" />

                    <span class="absolute -right-1 -bottom-1">
                        <span class="bg-sidebar flex size-3.5 items-center justify-center rounded-full">
                            <span
                                class="flex h-2 w-2 rounded-full border"
                                :class="{
                                    'bg-green-600 border-green-600': $store.workspace.isOnline(user),
                                    'bg-sidebar border-gray-400': ! $store.workspace.isOnline(user),
                                }"
                            ></span>
                        </span>
                    </span>
                </span>

                <span x-text="user.name"></span>
            </span>
        </li>
    </template>
</ul>

@script
    <script>
        Alpine.data('users', () => {
            return {
                users: @js($users),
                init() {
                    this.$store.workspace.join();
                },
            };
        });
    </script>
@endscript
