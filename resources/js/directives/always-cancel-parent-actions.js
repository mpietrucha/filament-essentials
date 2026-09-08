export default Alpine => {
    Alpine.directive('always-cancel-parent-actions', (el, _, { evaluate }) => {
        Alpine.nextTick(() => {
            const id = el.closest('.fi-modal')?.id

            if (!id) {
                return
            }

            const abortController = new AbortController()

            window.addEventListener(
                'modal-closed',
                event => {
                    if (event.detail.id !== id) {
                        return
                    }

                    abortController.abort()

                    evaluate('$wire.unmountAction(true)')

                    event.stopImmediatePropagation()
                },
                { capture: true, signal: abortController.signal },
            )
        })
    })
}
