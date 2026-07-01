import alwaysCancelParentActions from './directives/always-cancel-parent-actions'
import pasteSpreadsheet from './directives/paste-spreadsheet'

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(alwaysCancelParentActions)
    window.Alpine.plugin(pasteSpreadsheet)
})
