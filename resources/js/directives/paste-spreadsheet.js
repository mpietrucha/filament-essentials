export default Alpine => {
    Alpine.directive('paste-spreadsheet', el => {
        const input = el.querySelector('input')

        if (!input) {
            return
        }

        input.addEventListener('paste', e => {
            const grid = getPasteGrid(e)

            if (!isPastedGridMultidimensional(grid)) {
                return
            }

            e.preventDefault()

            const scope = el.closest('[wire\\:id]') ?? document

            const wrappers = [...scope.querySelectorAll('[x-paste-spreadsheet]')]
            const index = wrappers.indexOf(el)

            const columns = el.parentElement.querySelectorAll(
                ':scope > [x-paste-spreadsheet]',
            ).length

            const row = Math.floor(index / columns)
            const column = index % columns

            grid.forEach((cells, rowOffset) => {
                cells.forEach((value, columnOffset) => {
                    if (column + columnOffset >= columns) {
                        return
                    }

                    const target = wrappers[(row + rowOffset) * columns + column + columnOffset]

                    if (!target) {
                        return
                    }

                    setPasteTargetValue(target, value)
                })
            })
        })
    })
}

const getPasteGrid = e => {
    const clipboardData = e.clipboardData.getData('text/plain')

    const rows = clipboardData
        .replace(/[\r\n]+$/, '')
        .split(/\r?\n/)
        .map(row => {
            return row.split('\t').map(cell => cell.trim())
        })

    return rows.filter(row => row.some(Boolean))
}

const setPasteTargetValue = (target, value) => {
    const input = target.querySelector('input')

    if (!input) {
        return
    }

    input.value = value
    input.dispatchEvent(new Event('input', { bubbles: true }))
    input.dispatchEvent(new Event('change', { bubbles: true }))
}

const isPastedGridMultidimensional = grid => {
    if (grid.length > 1) {
        return true
    }

    return grid[0]?.length > 1
}
