export default Alpine => {
    Alpine.directive('decimal', (el, { expression }, { evaluate }) => {
        const fractionDigits = expression ? evaluate(expression) : 2

        el.addEventListener('change', () => {
            const value = convertToDecimal(el.value)

            if (isNaN(value)) {
                return
            }

            el.value = value.toFixed(fractionDigits)
        })
    })
}

const DOT = '.'
const COMMA = ','

const convertToDecimal = value => {
    const decimal = value.lastIndexOf(COMMA) > value.lastIndexOf(DOT) ? COMMA : DOT
    const thousands = decimal === COMMA ? DOT : COMMA

    return parseFloat(value.split(thousands).join('').replace(decimal, DOT))
}
