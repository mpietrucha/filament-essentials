export default Alpine => {
    Alpine.directive('hides-for-nested-modals', (el, _, { cleanup }) => {
        const onModalClosed = event => {
            const modal = document.getElementById(event.detail.id)

            if (!el.contains(modal)) {
                return
            }

            el.classList.remove('invisible')
        }

        const onModalOpened = event => {
            const modal = document.getElementById(event.detail.id)

            if (!el.contains(modal)) {
                return
            }

            el.classList.add('invisible')
        }

        window.addEventListener('modal-closed', onModalClosed)
        document.addEventListener('x-modal-opened', onModalOpened)

        cleanup(() => {
            window.removeEventListener('modal-closed', onModalClosed)
            document.removeEventListener('x-modal-opened', onModalOpened)
        })
    })
}
