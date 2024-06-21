addBlurAuthor();

function addBlurAuthor() {
    $(".input-multi[name*='author']").each(function() {
    $(this).blur(function(e) {
        var end = $(`input[name*='author']`).length;
        if($(this).val() == "" && end > 1){
            updateValueAuthor(parseInt($(this).attr("data-number")), end)
        }
    })
    })
}

function updateValueAuthor(start, end) {
    for (let i = start; i < end; i++){
        let currentInput = $(`input[name='author-${i}']`);
        let next = i + 1;
        let nextInput = $(`input[name='author-${next}']`);
        currentInput.val(nextInput.val())
    }
    $(`input[name='author-${end}']`).parent().remove();
    }

$(`.add-author`).click( function ( e )
{
    e.preventDefault();
    var inputBox = $(`.author-box`).last();
    var input = inputBox.children( "input" );
    var nextIndex = parseInt(input.attr( "data-number" )) + 1;
    var newInput = `<div class="col author-box" style="padding-right: 0">
                        <input name="author-${nextIndex}" id="author" type="text" class="input-multi form-control" data-number="${ nextIndex }">
                    </div>`
    $(newInput).insertAfter( inputBox );
    $(`input[name='author-${nextIndex}']`).focus();
    addBlurAuthor();                
} )