console.log('assets/js/api.js is called.');

let apiQB = function()
{
    this.getCompanyInfo = function()
    {
        //alert('getCompanyInfo called.');
        $.ajax({
            type: 'GET',
            url: 'api.php',
        }).done(function(msg) {
            $('#apiResult').html(msg);
        });
    }
}