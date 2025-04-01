$(function(){
        
    let datePicker = document.getElementById('datePicker');
    let picker = new Litepicker({
        element: datePicker,
        format: 'DD MMMM YYYY'
    });
    
    let dateRangePicker = document.getElementById('dateRangePicker');
    let pickerRange = new Litepicker({
        element: dateRangePicker,
        format: 'DD MMMM YYYY',
        singleMode: false,
    });
});