document.addEventListener('DOMContentLoaded', function () {

    var checkbox = document.getElementById('aio_hide_login_url_toggle');
    var formTables = document.querySelectorAll('.form-table');
    if (formTables.length >= 2 && checkbox) {
        function hide_admin_toggleRowsVisibility() {
            var displayValue = checkbox.checked ? '' : 'none';

            formTables[1].querySelectorAll('tr:nth-child(2), tr:nth-child(3)').forEach(function (row) {
                row.style.display = displayValue;
            });
        }
        hide_admin_toggleRowsVisibility();
        checkbox.addEventListener('change', hide_admin_toggleRowsVisibility);
    }


    
    /************* For show and hide the search excluder section  ****************/
        var search_checkbox = document.getElementById('aio_search_excluder');
       
            var selectBox = document.querySelector('.post-exclude-show');
            if (search_checkbox && selectBox) {
                // Initially set the display based on the checkbox state
                selectBox.style.display = search_checkbox.checked ? 'block' : 'none';
                // Toggle visibility on checkbox change
                search_checkbox.addEventListener('change', function () {
                    selectBox.style.display = this.checked ? 'block' : 'none';
                });                
            } 
});

/* ---- customizer text area count ---- */
function updateLineNumbers() {
    var textarea = document.getElementById('message');
    var codeLines = textarea.value.split('\n');
    var lineNumbersWrapper = document.querySelector('.code-line-numbers');

    var lineNumbersHtml = '';
    for (var i = 1; i <= codeLines.length; i++) {
        lineNumbersHtml += '<span>' + i + '</span>';
    }

    lineNumbersWrapper.innerHTML = lineNumbersHtml;
}

jQuery(document).ready(function() {
    updateLineNumbers(); 
    document.getElementById('message').addEventListener('input', updateLineNumbers);

    var textarea = jQuery(".code-textarea-wrapper textarea");
    var lineNumbers = jQuery(".code-line-numbers");

    textarea.scroll(function() {
        lineNumbers.scrollTop(textarea.scrollTop());
    });

    lineNumbers.scroll(function() {
        textarea.scrollTop(lineNumbers.scrollTop());
    });
});

/*---- customozer hide show in admin site  ---*/
document.addEventListener("DOMContentLoaded", function() {
    var checkbox = document.getElementById("aio_custom_css_toggle");
    var codeEditor = document.querySelector(".customizers-code-editor");

    checkbox.addEventListener("change", function() {
        if (this.checked) {
            codeEditor.style.display = "flex"; 
        } else {
            codeEditor.style.display = "none"; 
        }
    });
    if (!checkbox.checked) {
        codeEditor.style.display = "none";
    }
});

/*------ ajax to delete exclude post -------*/

jQuery(document).on('click','.exclude-post-delete',function(e){
    e.preventDefault();    
    var row = jQuery(this); 
    row.parent().parent().addClass('deleted');
    var post_id = jQuery(this).attr('post-id');    
    jQuery.ajax({
    type : "POST", 
    url : ajaxurl,
    dataType: 'json',
    data : {action:'exclude_post_visible_ajax',post_id},
        success: function (response) {
            console.log(response);
            if (response.success == true) {
                console.log('yes');
               row.parent().parent().remove();
        }
    }
    })
})

