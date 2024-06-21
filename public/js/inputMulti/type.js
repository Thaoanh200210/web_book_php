addBlurType(); 

function addBlurType() {
    $(".input-multi[name*='type']").each(function() {
    $(this).blur(function(e) {
        var end = $(`input[name*='type']`).length;
        if($(this).val() == "" && end > 1){
            updateValueType(parseInt($(this).attr("data-number")), end)
        }
    })
    })
}

function updateValueType(start, end) {
    for (let i = start; i < end; i++){
        let currentInput = $(`input[name='type-${i}']`);
        let next = i + 1;
        let nextInput = $(`input[name='type-${next}']`);
        currentInput.val(nextInput.val())
        }
    $(`input[name='type-${end}']`).parent().remove();
    }

$(`.add-type`).click( function ( e )
{
    e.preventDefault();
    var inputBox = $(`.type-box`).last();
    var input = inputBox.children( "input" );
    var nextIndex = parseInt(input.attr( "data-number" )) + 1;
    var newInput = `<div class="col type-box" style="padding-right: 0">
                        <input name="type-${nextIndex}" id="type" type="text" class="input-multi form-control" data-number="${ nextIndex }">
                    </div>`
    $( newInput ).insertAfter( inputBox );
    $(`input[name='type-${nextIndex}']`).focus();
    addBlurType();                
} )