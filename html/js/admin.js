function updatePage() {

}

function getRandomId(a){return a?(0|Math.random()*16).toString(16):(""+1e7+-1e3+-4e3+-8e3+-1e11).replace(/1|0/g,getRandomId)}


$(document).ready(function() {

    $('#brewed').datetimepicker({
        //calendarWeeks: true,
        locale: 'nb',
        format: 'L'
    });
    $('#tapped').datetimepicker({
        //calendarWeeks: true,
        locale: 'nb',
        format: 'L'
    });



    $('form.page-edit').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        form.find('button.has-spinner').addClass('spinning');
        form.find('button.has-spinner').addClass('disabled');
        form.find('button.has-spinner span.update').html('Lagrer...');

        form.find('textarea').each(function( index ) {
            var textAreaId = $(this).attr('id');
            if($('#' + textAreaId).trumbowyg('html') != false) {
                $('#' + textAreaId).html($('#' + textAreaId).trumbowyg('html'));
            }
        });

        //alert(formData);

        $.get('/admin/save.php?action=pageSave&' + formData , { '_': $.now() } , function() {}) // '_': $.now() = prevent caching
            .done(function(data) {
                var status = data.status;

                form.find('button.has-spinner').removeClass('spinning');
                form.find('button.has-spinner').removeClass('disabled');

                if(status == 'saved') {
                    //yay
                    form.find('button.has-spinner span.update').html('Lagre innhold');
                    var randomId = getRandomId('');
                    form.find('button.has-spinner').after('<span id="'+ randomId +'" class="glyphicon glyphicon-ok-sign"></span>');
                    setTimeout(function() {
                        $('#'+randomId).remove();
                    }, 5000);
                } else if(status == 'failed') {
                    var randomId = getRandomId('');
                    form.find('button.has-spinner span.update').html('Lagring feilet');
                    form.find('button.has-spinner').after('<span id="'+ randomId +'" class="glyphicon glyphicon-warning-sign"></span>');
                    setTimeout(function() {
                        $('#'+randomId).remove();
                    }, 20000);
                }

            })
            .fail(function(data) {
                alert( "error" );
            });

    });

    $('form.page-new-edit').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        form.find('button.has-spinner').addClass('spinning');
        form.find('button.has-spinner').addClass('disabled');
        form.find('button.has-spinner span.update').html('Lagrer...');

        form.find('textarea').each(function( index ) {
            var textAreaId = $(this).attr('id');
            if($('#' + textAreaId).trumbowyg('html') != false) {
                $('#' + textAreaId).html($('#' + textAreaId).trumbowyg('html'));
            }
        });

        //alert(formData);

        $.get('/admin/save.php?action=newPage&' + formData , { '_': $.now() } , function() {}) // '_': $.now() = prevent caching
            .done(function(data) {
                var status = data.status;
                var randomId = getRandomId('');
                var randomIdMessage = getRandomId('');

                form.find('button.has-spinner').removeClass('spinning');

                if(status == 'saved') {
                    //yay
                    form.find('button.has-spinner span.update').html('Lagre innhold');
                    form.find('button.has-spinner').after('<span id="'+ randomId +'" class="glyphicon glyphicon-ok-sign"></span>');
                    form.find('button.has-spinner').after('<p id="'+ randomIdMessage +'" class="bg-success">'+ data.message +'</p>');

                    setTimeout(function() {
                        $('#'+randomId).remove();
                        window.location.replace('/admin/index.php');
                    }, 5000);
                } else if(status == 'failed') {

                    form.find('button.has-spinner span.update').html('Lagring feilet');
                    form.find('button.has-spinner').removeClass('disabled');

                    if(data.message) {
                        form.find('button.has-spinner').after('<p id="'+ randomIdMessage +'" class="bg-danger">'+ data.message +'</p>');
                    }

                    if(data.element) {
                        $('#'+data.element).parent().parent().addClass('has-error');
                    }

                    form.find('button.has-spinner').after('<span id="'+ randomId +'" class="glyphicon glyphicon-warning-sign"></span>');
                    setTimeout(function() {
                        $('#'+randomId).remove();
                        $('#'+randomIdMessage).remove();
                    }, 10000);
                }

            })
            .fail(function(data) {
                alert( "error" );
            });

    });
});
