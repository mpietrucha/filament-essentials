export default Alpine => {
    Alpine.directive('paste-spreadsheet', el => {
        const input = el.querySelector('input')

        if (!input) {
            return
        }

        input.addEventListener('paste', e => {
            const grid = buildPasteGrid(e)

            if (isPastedGridSingleCell(grid)) {
                return
            }

            e.preventDefault()

            const rows = buildPasteRows(getPasteScope(el))

            const position = findPastePosition(rows, el)

            if (!position) {
                return
            }

            applyPasteGrid(grid, rows, position)
        })
    })
}

const getPasteScope = el => el.closest('[wire\\:id]') ?? document

const buildPasteRows = scope => {
    const fields = [...scope.querySelectorAll('[data-field-wrapper]')]

    const rows = []
    let currentRow = null

    fields.forEach(field => {
        if (field.hasAttribute('x-paste-spreadsheet')) {
            currentRow = []
            rows.push(currentRow)
        }

        if (currentRow) {
            currentRow.push(field)
        }

        if (field.hasAttribute('x-paste-spreadsheet-finish')) {
            currentRow = null
        }
    })

    return rows
}

const isPastedGridSingleCell = grid => grid.length === 1 && grid[0].length === 1

const findPastePosition = (rows, el) => {
    const row = rows.findIndex(cells => cells.includes(el))

    if (row === -1) {
        return null
    }

    return { row, cell: rows[row].indexOf(el) }
}

const buildPasteGrid = (e, rowDelimiter = '\n', cellDelimiter = '\t') => {
    const clipboardData = e.clipboardData.getData('text/plain')

    const rows = clipboardData
        .trimEnd()
        .split(rowDelimiter)
        .map(row => row.split(cellDelimiter).map(cell => cell.trim()))

    return rows.filter(row => row.some(Boolean))
}

const applyPasteGrid = (grid, rows, position) => {
    const { row, cell } = position

    grid.forEach((cells, offset) => {
        const targetRow = rows[row + offset]

        if (!targetRow) {
            return
        }

        cells.forEach((value, offset) => {
            const target = targetRow[cell + offset]

            if (!target) {
                return
            }

            setPasteTargetValue(target, value)
        })
    })
}

const setPasteTargetValue = (target, value) => {
    const input = target.querySelector('input')

    if (!input) {
        return
    }

    input.value = value
    input.dispatchEvent(new Event('input', { bubbles: true }))
}
