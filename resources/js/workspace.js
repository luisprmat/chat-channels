/**
 * The "workspace" presence channel is joined once and kept alive while the
 * user moves between chat pages with wire:navigate, so other users don't see
 * a leave/join flicker. It is left as soon as the user lands on a page that
 * doesn't render the workspace (marked with the `data-workspace` attribute).
 */
let channel = null;

document.addEventListener('alpine:init', () => {
    Alpine.store('workspace', {
        online: [],

        join() {
            if (channel) {
                return;
            }

            channel = Echo.join('workspace')
                .here((users) => (this.online = users.map((user) => user.id)))
                .joining((user) => this.online.push(user.id))
                .leaving((user) => (this.online = this.online.filter((id) => id !== user.id)))
                .listen('.ChannelCreated', (event) => {
                    window.dispatchEvent(new CustomEvent('workspace-channel-created', { detail: event.model }));
                });
        },

        leave() {
            if (!channel) {
                return;
            }

            Echo.leave('workspace');
            channel = null;
            this.online = [];
        },

        isOnline(user) {
            return this.online.includes(user.id);
        },
    });
});

document.addEventListener('livewire:navigated', () => {
    if (!document.querySelector('[data-workspace]')) {
        Alpine.store('workspace').leave();
    }
});
