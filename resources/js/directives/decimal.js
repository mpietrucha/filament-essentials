export default Alpine => {
    Alpine.directive('decimal', (el, { expression }, { evaluate, cleanup }) => {
        const componentId = evaluate('$wire.__instance.id')

        const fractionDigits = expression ? evaluate(expression) : 2

        const format = () => {
            const value = convertToDecimal(el.value)

            if (isNaN(value)) {
                return
            }

            el.value = ''

            el.value = value.toFixed(fractionDigits)
        }

        format()
        el.addEventListener('change', format)

        const stopListening = Livewire.hook('commit', ({ component, succeed }) => {
            if (component.id !== componentId) {
                return
            }

            succeed(() => document.activeElement !== el && format())
        })

        cleanup(() => stopListening())
    })
}

const DOT = '.'
const COMMA = ','

const convertToDecimal = value => {
    const decimal = value.lastIndexOf(COMMA) > value.lastIndexOf(DOT) ? COMMA : DOT
    const thousands = decimal === COMMA ? DOT : COMMA

    return parseFloat(value.split(thousands).join('').replace(decimal, DOT))
}
