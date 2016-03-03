$(function(){
    loadFile();
});

function loadFile(){
    $('#inputBtnFile').click(function() {
        $('#inputFile').click();
    });
    $('#inputFile').change(function(){
        $('#inputFileName').val($(this).val().substring($(this).val().lastIndexOf('\\')+1,$(this).val().length));
        $('#inputFileName').trigger('blur');
        if( $('#inputFileName').val()==""){
            $('#inputFileName-error').hide();
        }
    });
}
