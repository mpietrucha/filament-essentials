export default Alpine => {
    Alpine.directive('paste-spreadsheet', (el, _, { evaluate }) => {
        const target = getPasteTarget(el)

        if (!target) {
            return
        }

        const wire = evaluate('$wire')

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

            applyPasteGrid(grid, rows, position, wire)
        })
    })
}

const getPasteTarget = el => {
    return el.querySelector('input') ?? el.querySelector('select') ?? el.querySelector('textarea')
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

const applyPasteGrid = async (grid, rows, position, wire) => {
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

            setPasteTargetValue(target, value, wire)
        })
    })
}

const setPasteTargetValue = async (target, value, wire) => {
    const input = getPasteTargetInput(target)

    if (!input) {
        return
    }

    const attribute = [...input.attributes].find(attribute =>
        attribute.name.startsWith('wire:model'),
    )

    if (!attribute) {
        input.value = value
        input.dispatchEvent(new Event('input', { bubbles: true }))

        return
    }

    await wire.set(attribute.value, value, attribute.name.split('.').includes('live'))
}
