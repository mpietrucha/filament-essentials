export default Alpine => {
    Alpine.directive('paste-spreadsheet', (el, _, { evaluate }) => {
        const target = getTarget(el)

        if (!target) {
            return
        }

        const livewire = evaluate('$wire')

        target.addEventListener('paste', async event => {
            const inputGrid = getInputGrid(event)

            if (!inputGrid) {
                return
            }

            event.preventDefault()

            const outputGrid = getOutputGrid(el)

            if (!outputGrid) {
                return
            }

            for (const [wrapper, value] of getPairs(inputGrid, outputGrid)) {
                await setValue(wrapper, value, livewire)
            }
        })
    })
}

const getTarget = el => el.querySelector('input, select, textarea')

const getInputGrid = event => {
    const clipboardData = event.clipboardData.getData('text/plain')

    const grid = clipboardData
        .trimEnd()
        .split('\n')
        .map(row => row.split('\t').map(cell => cell.trim()))

    if (grid.length === 1 && grid[0].length === 1) {
        return null
    }

    return grid
}

const getOutputGrid = el => {
    const scope = el.closest('[wire\\:id]') ?? document

    const wrappers = [...scope.querySelectorAll('[data-field-wrapper]')]

    let row = null
    const grid = []

    wrappers.forEach(wrapper => {
        if (wrapper.hasAttribute('x-paste-spreadsheet')) {
            row = []
            grid.push(row)
        }

        if (row) {
            row.push(wrapper)
        }

        if (wrapper.hasAttribute('x-paste-spreadsheet-finish')) {
            row = null
        }
    })

    const startRow = grid.findIndex(cells => cells.includes(el))

    if (startRow === -1) {
        return null
    }

    const startCell = grid[startRow].indexOf(el)

    return grid.slice(startRow).map(cells => cells.slice(startCell))
}

const getPairs = (inputGrid, outputGrid) => {
    const pairs = []

    inputGrid.forEach((cells, row) => {
        const outputRow = outputGrid[row] ?? []

        cells.forEach((value, cell) => {
            const wrapper = outputRow[cell]

            if (wrapper) {
                pairs.push([wrapper, value])
            }
        })
    })

    return pairs
}

const setValue = async (wrapper, value, livewire) => {
    const target = getTarget(wrapper)

    if (!target) {
        return
    }

    const binding = getModelBinding(target)

    if (!binding) {
        target.value = value

        target.dispatchEvent(new Event('input', { bubbles: true }))
        target.dispatchEvent(new Event('change', { bubbles: true }))

        return
    }

    await livewire.set(binding.path, value, binding.live).catch(() => {})
}

const getModelBinding = target => {
    const modelAttribute = [...target.attributes].find(attribute =>
        attribute.name.startsWith('wire:model'),
    )

    if (!modelAttribute) {
        return null
    }

    return { path: modelAttribute.value, live: modelAttribute.name.split('.').includes('live') }
}
