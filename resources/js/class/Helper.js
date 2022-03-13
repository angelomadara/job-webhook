class Helper{
    /**
     * tooltip
     * put this to mounted hook to enable the bootstrap tooltip
     */
    tooltip(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {return new bootstrap.Tooltip(tooltipTriggerEl)})
    }

    thousandSeparator(number){
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
}

export default Helper = new Helper();
