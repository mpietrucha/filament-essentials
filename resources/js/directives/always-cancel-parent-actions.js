export default Alpine => {
    Alpine.directive('always-cancel-parent-actions', (el, _, { evaluate }) => {
        Alpine.nextTick(() => {
            const id = el.closest('.fi-modal')?.id

            if (id === undefined) {
                return
            }

            const controller = new AbortController()

            window.addEventListener(
                'modal-closed',
                e => {
                    if (e.detail.id !== id) {
                        return
                    }

                    controller.abort()

                    evaluate('$wire.unmountAction(true)')

                    e.stopImmediatePropagation()
                },
                { capture: true, signal: controller.signal },
            )
        })
    })
}
