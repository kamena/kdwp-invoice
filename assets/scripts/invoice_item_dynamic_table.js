jQuery(document).ready(function($){
	var input_value = $('input#isRow').val();
	// input_value++;
	var row_count = input_value;
    $("#add_row").click(function(){
    	$('#tab_logic').append('<tr id="addr'+input_value+'"></tr>');
    	$('#addr'+input_value).html("\
    		<input type='hidden' name='isRow' value='"+input_value+"'/>\
    		<td><input type='number' name='num"+input_value+"' value='"+input_value+"' style='width: 50px'></td>\
			<td>\
				<input type='text' name='name"+input_value+"'  placeholder='Продукт' class='form-control' />\
			</td>\
			<td>\
				<input type='text' name='quantity"+input_value+"' placeholder='Количество' class='form-control'/>\
			</td>\
			<td>\
				<select>\
					<option value''>Мярка</option>\
					<option value'1'>бр.</option>\
					<option value'2'>кг.</option>\
				</select>\
			</td>\
			<td>\
				<input type='text' name='price"+input_value+"' placeholder='Ед. цена' class='form-control'/>\
			</td>\
			<td>\
				<button name='del"+input_value+"' class='btn btn-danger row-remove'>x</button>\
			</td>"	);
     	input_value++; 
     	row_count++;
     	$('input#invoice_item_column_number').val(row_count);
	});


    $(document).on('click', '.row-remove', function() {
        var $row = $(this).closest('tr');
        $row.remove();

        row_count--;
        $('input#invoice_item_column_number').val(row_count);
    });

    $('input#invoice_item_column_number').val(input_value);

});