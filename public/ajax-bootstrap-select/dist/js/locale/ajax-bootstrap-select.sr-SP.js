/*!
 * Ajax Bootstrap Select
 *
 * Extends existing [Bootstrap Select] implementations by adding the ability to search via AJAX requests as you type. Originally for CROSCON.
 *
 * @version 1.4.5
 * @author Adam Heim - https://github.com/truckingsim
 * @link https://github.com/truckingsim/Ajax-Bootstrap-Select
 * @copyright 2019 Adam Heim
 * @license Released under the MIT license.
 *
 * Contributors:
 *   Mark Carver - https://github.com/markcarver
 *
 * Last build: 2019-09-24 9:36:53 AM CDT
 */
!(function ($) {
    /*!
     * Serbian translation for the "sr-SP" and "sr" language codes.
     * Miroslav Maksimovic <miroslavmaksimovic95@gmail.com>
     */
    $.fn.ajaxSelectPicker.locale['sr-SP'] = {

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} currentlySelected = 'Currently Selected'
         * @markdown
         * The text to use for the label of the option group when currently selected options are preserved.
         */
        currentlySelected: 'Trenutno izabrano',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} emptyTitle = 'Select and begin typing'
         * @markdown
         * The text to use as the title for the select element when there are no items to display.
         */
        emptyTitle: 'Izaberite i pocnite kucati',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} errorText = ''Unable to retrieve results'
         * @markdown
         * The text to use in the status container when a request returns with an error.
         */
        errorText: 'Nemoguce dobiti rezultate',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} searchPlaceholder = 'Search...'
         * @markdown
         * The text to use for the search input placeholder attribute.
         */
        searchPlaceholder: 'Pretrazi...',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} statusInitialized = 'Start typing a search query'
         * @markdown
         * The text used in the status container when it is initialized.
         */
        statusInitialized: 'Pocnite kucati kljucnu rijec',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} statusNoResults = 'No Results'
         * @markdown
         * The text used in the status container when the request returns no results.
         */
        statusNoResults: 'Nema rezultata',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} statusSearching = 'Searching...'
         * @markdown
         * The text to use in the status container when a request is being initiated.
         */
        statusSearching: 'Trazim...',

        /**
         * @member $.fn.ajaxSelectPicker.locale
         * @cfg {String} statusTooShort = 'Please enter more characters'
         * @markdown
         * The text used in the status container when the request returns no results.
         */
        statusTooShort: 'Molimo unesite vise znakova'
    };
    $.fn.ajaxSelectPicker.locale.ru = $.fn.ajaxSelectPicker.locale['sr-SP'];
})(jQuery);
