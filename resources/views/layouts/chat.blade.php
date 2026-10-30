<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>
        {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("images/apple-touch-icon.png") }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("images/favicon-32x32.png") }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("images/favicon-16x16.png") }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset("images/favicon.ico") }}" />

    @fonts('sans')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen w-screen antialiased">
    <div class="flex h-full w-full flex-col items-center justify-center bg-linear-to-tr from-fuchsia-600 from-10% to-fuchsia-900 p-1">
        <div class="flex w-full items-center justify-between px-2 py-1.5">
            <!-- Window actions -->
            <div class="flex gap-x-2">
                <span class="size-3 rounded-full bg-red-400"></span>

                <span class="size-3 rounded-full bg-yellow-400"></span>

                <span class="size-3 rounded-full bg-green-400"></span>
            </div>

            <!-- Controls -->
            <div class="flex w-1/2 items-center gap-x-2">
                <x-icons.back class="size-5 text-white/20" />

                <x-icons.forward class="size-5 text-white/20" />

                <x-icons.clock class="size-5 text-white" />

                <input
                    type="search"
                    class="w-full rounded-sm bg-white/20 px-3 py-1 text-sm placeholder:text-white"
                    placeholder="{{ __('Search :name', ['name' => 'Laravel']) }}"
                />
            </div>

            <!-- Help -->
            <a href="{{ route("dashboard") }}">
                <x-icons.question class="size-5 text-white" />
            </a>
        </div>

        <div class="flex w-full">
            <!-- Sidebar -->
            <div class="flex flex-col justify-between py-2 pr-5 pl-3.5">
                <ul class="flex flex-col gap-y-4">
                    <li>
                        <img src="{{ asset("images/laravel.png") }}" alt="{{ __('Avatar') }}" class="size-10 rounded-md" />
                    </li>

                    <li class="flex size-10 items-center justify-center rounded-md bg-white/20 text-white">
                        <x-icons.home class="size-6" />
                    </li>
                </ul>

                <div
                    x-data="{
                        open: false,
                        toggle() {
                            if (this.open) {
                                return this.close();
                            }

                            this.$refs.button.focus();

                            this.open = true;
                        },
                        close(focusAfter) {
                            if (! this.open) return;

                            this.open = false;

                            focusAfter && focusAfter.focus();
                        },
                    }"
                    x-on:keydown.escape.prevent.stop="close($refs.button)"
                    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                    x-id="['dropdown-button']"
                    class="relative flex flex-col gap-4"
                >
                    <!-- Trigger -->
                    <button
                        x-ref="button"
                        x-on:click="toggle()"
                        :aria-expanded="open"
                        :aria-controls="$id('dropdown-button')"
                        type="button"
                        class="relative"
                    >
                        <img
                            src="{{ Auth::user()->avatar }}"
                            alt="{{ Auth::user()->name }}"
                            class="size-10 rounded-md"
                        />

                        <span class="absolute -right-1 -bottom-1">
                            <span class="flex size-3.5 items-center justify-center rounded-full bg-fuchsia-600">
                                <span class="flex size-2 rounded-full bg-green-600"></span>
                            </span>
                        </span>
                    </button>

                    <!-- Panel -->
                    <div
                        x-ref="panel"
                        x-show="open"
                        x-transition.origin.bottom.left
                        x-on:click.outside="close($refs.button)"
                        :id="$id('dropdown-button')"
                        x-cloak
                        class="bg-sidebar absolute bottom-full left-0 z-10 mb-2 min-w-60 origin-bottom-left rounded-lg border border-gray-200 p-2 shadow-sm outline-none"
                    >
                        <div class="flex items-center gap-2 border-b p-2">
                            <img
                                src="{{ Auth::user()->avatar }}"
                                alt="{{ Auth::user()->name }}"
                                class="size-10 rounded-md"
                            />

                            <div class="flex flex-col overflow-x-auto scrollbar-thin scrollbar-thumb-sidebar">
                                <div class="font-bold">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-slate-600">{{ Auth::user()->email }}</div>
                            </div>
                        </div>

                        <form action="{{ route("logout") }}" method="post" class="mt-2">
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-gray-800 transition-colors hover:bg-fuchsia-900 hover:text-white focus-visible:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 lg:py-1.5"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>

                                <span>{{ __('Log out') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Workspace -->
            {{ $slot }}
        </div>
    </div>
</body>
</html>
