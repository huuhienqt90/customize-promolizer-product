/**
 * Created by LuckyStar on 8/26/14.
 */
jQuery(document).ready(function ($){
    //Begin Product Color Page
    var options = {
        color: false,
        mode: 'hsl',
        controls: {
            horiz: 's', // horizontal defaults to saturation
            vert: 'l', // vertical defaults to lightness
            strip: 'h' // right strip defaults to hue
        },
        hide: true, // hide the color picker by default
        border: true, // draw a border around the collection of UI elements
        target: false, // a DOM element / jQuery selector that the element will be appended within. Only used when called on an input.
        width: 200, // the width of the collection of UI elements
        palettes: true, // show a palette of basic colors beneath the square.
        change: function(event, ui) {
            // event = standard jQuery event, produced by whichever control was changed.
            // ui = standard jQuery UI object, with a color member containing a Color.js object

            // change the headline color
            $(this).css( 'background', ui.color.toString());
        }
    };
    $('.hex_code').iris(options);
    $(document).click(function (e) {
        if (!$(e.target).is(".hex_code, .iris-picker, .iris-picker-inner")) {
            $('.hex_code').iris('hide');
        }
    });
    $('.hex_code').click(function (event) {
        $('.hex_code').iris('hide');
        $(this).iris('show');
        return false;
    });
    if( $( "#two-color" ).is( ":checked" ) ){
        $( ".ColorTwo" ).show();
    }else{
        $( ".ColorTwo" ).hide();
    }
    $( "#two-color" ).change(function (){
        if( $(this).is( ":checked" ) ){
            $( ".ColorTwo" ).show();
        }else{
            $( ".ColorTwo" ).hide();
        }
    });
    $("span.delete a").click(function(){
        if (confirm("Are you sure to delete this record?")) {
            return true;
        }else{
            return false;
        }
    });
    //END Product Color Page

    //BEGIN Imprint Type
    var custom_uploader;


    $('#upload_image_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#image').val(attachment.url);
            $('.izwImageThubnail img').attr('src',attachment.sizes.thumbnail.url);
            $('#remove_image').show();
            $('#upload_image_button').hide();
        });

        //Open the uploader dialog
        custom_uploader.open();
        return false;
    });
    //END Imprint Type
    //BEGIN General Settings Page
    $("#izw_cart_size").chosen({width: "100%"});
    $("#izw_cart_imprint_location").chosen({width: "100%"});
    if($("#izw_flat_rate_shipping").is(":checked")){
        $('label[for="izw_flat_rate_amount"]').show();
    }else{
        $('label[for="izw_flat_rate_amount"]').hide();
    }
    $("#izw_flat_rate_shipping").change(function(){
        if($(this).is(":checked")){
            $('label[for="izw_flat_rate_amount"]').show();
        }else{
            $('label[for="izw_flat_rate_amount"]').hide();
        }
    });
    //END General Settings Page
    //BEGIN Custom Product Data Tab
    $("#_izw_product_colors").chosen({width: "100%"});
    $("#_izw_imprint_types").chosen({width: "100%"});
    $("#_izw_imprint_locations").chosen({width: "100%"});
    $("#_izw_imprint_colors").chosen({width: "100%"});
    $( "#_izw_sale_from" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#_izw_sale_to" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "img._izw_date_from").click(function (){
        $( "#_izw_sale_from").focus();
    });
    $( "img._izw_date_to").click(function (){
        $( "#_izw_sale_to").focus();
    });
    if($( "#_izw_onsale").is(":checked")){
        $(".sale.active").removeClass('hide');
    }else{
        $(".sale.active").addClass('hide');
    }
    $( "#_izw_onsale").change(function () {
        if($(this).is(":checked")){
            $(".sale.active").removeClass('hide');
        }else{
            $(".sale.active").addClass('hide');
        }
    });
    if($( "#_izw_override_shipping").is(":checked")){
        $(".shipping").removeClass('hide');
    }else{
        $(".shipping").addClass('hide');
    }
    $( "#_izw_override_shipping").change(function () {
        if($(this).is(":checked")){
            $(".shipping").removeClass('hide');
        }else{
            $(".shipping").addClass('hide');
        }
    });
    $("#_izw_imprint_type_dnmprc").change(function () {
        var PID = $(this).val();
        //ADD CLASS hide
        $("tr.msrp").addClass('hide');
        $("tr.price").addClass('hide');
        $("tr.sale").addClass('hide');
        $("tr.price").removeClass('active');
        $("tr.sale").removeClass('active');
        //REMOVE CLASS hide
        $("tr.msrp.ip_" + PID).removeClass('hide');
        $("tr.price.ip_" + PID).removeClass('hide');
        $("tr.price.ip_" + PID).addClass('active');
        $("tr.sale.ip_" + PID).addClass('active');
        if($( "#_izw_onsale").is(":checked")){
            $("tr.sale.ip_" + PID).removeClass('hide');
        }else{
            $("tr.sale.ip_" + PID).addClass('hide');
        }
    });
    $("tr.shipping td input, tr.sale td input, tr.price td input, tr.quantity td input, tr.msrp td input").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && $(this).val().indexOf('.') != -1 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    $("#addColumn").click(function (){
        var countColum = ($("table.dynamic-pricing thead tr.column_name td").length);
        var columnCount = countColum + 1;
        var columnNumber = countColum - 1;
        var columnName = '<td>Column '+countColum + '</td>';
        var qty = columnCount*48;
        var qtyHtml = '<td>Max Qty: <input type="text" name="_izw_max['+columnNumber+']" value="' + qty + '"/></td>';
        var shippingHtml = '<td>' +
        'Shipping: <input type="text" name="_izw_shipping['+columnNumber+']" value="2.00"/>' +
        '</td>';

        $('table.dynamic-pricing').find('tr').each(function(){
            if($(this).hasClass("column_name")){
                $(this).find('td').eq(countColum-2).after(columnName);
            }
            if($(this).hasClass("quantity")){
                $(this).find('td').eq(countColum-2).after(qtyHtml);
            }
            if($(this).hasClass("shipping")){
                $(this).find('td').eq(countColum-2).after(shippingHtml);
            }
            if($(this).hasClass("msrp")){
                $(this).find('td').eq(countColum-2).after('<td>' +
                    'MSRP: <input type="text" name="_izw_msrp['+columnNumber+']['+$(this).attr('data-imprintID')+']" value="2.00"/>' +
                    '</td>');
            }
            if($(this).hasClass("price")){
                $(this).find('td').eq(countColum-2).after('<td>' +
                    'Price: <input type="text" name="_izw_price['+columnNumber+']['+$(this).attr('data-imprintID')+']" value="2.00"/>' +
                    '</td>');
            }
            if($(this).hasClass("sale")){
                $(this).find('td').eq(countColum-2).after('<td>' +
                    'Sale: <input type="text" name="_izw_sale['+columnNumber+']['+$(this).attr('data-imprintID')+']" value="2.00"/>' +
                    '</td>');
            }
        });
        $("#countColumn").val(countColum);
        if($("#removeColumn").is(":hidden")){
            $("#removeColumn").show();
        }
        return false;
    });
    $("#removeColumn").click(function (){
        var countColum = ($("table.dynamic-pricing thead tr.column_name td").length)-2;
        if(countColum > 4){
            $('table.dynamic-pricing').find('tr').each(function(){
                $(this).find('td').eq(countColum).remove();
            });
            $("#countColumn").val(countColum);
        }
        if(countColum <=5 ){
            $(this).hide();
        }
        return false;
    });
    $("#_izw_imprint_types").chosen().change(function (e, params){
        var values = $("#_izw_imprint_types").chosen().val();
        var PID = $("#post_ID").val();
        var _izw_onsale = $("#_izw_onsale").val();
        var IPSL = $("#_izw_imprint_type_dnmprc").val();
        var data = {
            'action': 'update_imprint_type',
            'IPID': values
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data, function(response) {
            $("#_izw_imprint_type_default").html(response);
            $("#_izw_imprint_type_dnmprc").html(response);
        });

        var data2 = {
            'action': 'update_imprint_type_table',
            'IPID': values,
            '_izw_onsale': _izw_onsale,
            'PID': PID
        };

        $.post(ajaxurl, data2, function(response) {
            $("table.dynamic-pricing").html(response);
            if($( "#_izw_override_shipping").is(":checked")){
                $(".shipping").removeClass('hide');
            }else{
                $(".shipping").addClass('hide');
            }
            if($( "#_izw_onsale").is(":checked")){
                $(".sale.active").removeClass('hide');
            }else{
                $(".sale.active").addClass('hide');
            }
        });
    });
    //END Custom Product Data Tab
});