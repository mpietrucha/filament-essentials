import alwaysCancelParentActions from './directives/always-cancel-parent-actions'
import decimal from './directives/decimal'
import hidesForNestedModals from './directives/hides-for-nested-modals'
import pasteSpreadsheet from './directives/paste-spreadsheet'

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(alwaysCancelParentActions)
    window.Alpine.plugin(decimal)
    window.Alpine.plugin(hidesForNestedModals)
    window.Alpine.plugin(pasteSpreadsheet)
})
