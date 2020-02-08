
<div>
	<input type="file" id="my_file_input" />
	
</div>
<script type="text/javascript">

	$("#my_file_input").change(function (e) {
		filePicked(e);
	})

	function filePicked(oEvent) {
	    // Get The File From The Input
	    var oFile = oEvent.target.files[0];
	    var sFilename = oFile.name;
	    // Create A File Reader HTML5
	    var reader = new FileReader();
	    
	    // Ready The Event For When A File Gets Selected
	    reader.onload = function(e) {
	        var data = e.target.result;
	        var cfb = XLSX.read(data, {type: 'binary'});
	        // var wb = XLS.parse_xlscfb(cfb);
	        // Loop Over Each Sheet
	        cfb.SheetNames.forEach(function(sheetName) {
	            // Obtain The Current Row As CSV
	            var sCSV = XLS.utils.make_csv(cfb.Sheets[sheetName]);   
	            var oJS = XLS.utils.sheet_to_row_object_array(cfb.Sheets[sheetName]);   

	            $("#my_file_output").html(sCSV);
	            oJS.forEach(function (row) {
	            	let category = row.category.trim();
	            	let url_category = './index.php?action=getCategory&category='+category;

	            	let brand = row.brand.trim();
	            	let url_brand = './index.php?action=getCategory&brand='+brand;

	            	let get_category = new Promise((resolve, reject) => {
	            		$.get(url_category, function (a) {
		            		let category = JSON.parse(a);
		            		resolve(category)
		            	})
	            	})

	            	let get_brand = new Promise((resolve, reject) => {
	            		$.get(url_brand, function (a) {
		            		let brand = JSON.parse(a);
		            		resolve(brand)
		            	})
	            	})

	            	Promise.all([get_brand, get_category])
	            	.then(res => {

	            		expired_alert = new Date(row.expired_at);
	            		expired_alert.setDate(expired_alert.getDate()-31)
	            		date_expired = expired_alert.getFullYear()+"-"+parseInt(expired_alert.getMonth()+1)+"-"+expired_alert.getDate();
	            		
	            		let data = {
	            			'name': row.name.trim(),
	            			'kind':1,
							'code':row.code,
							'barcode':"",
							'unit':row.measure,
							'price_in':row.price_in.replace(",","."),
							'price_out':row.price_out.replace(",","."),
							'expired_at':row.expired_at,
							'expired_alert':date_expired,
							'brand_id':parseInt(res[0].id),
							'category_id':parseInt(res[1].id),
							'inventary_min':1,
							'q':row.stock,
	            		}

	            		let add_product_url = './index.php?view=addproduct';
	            		$.post(add_product_url, data)
	            		.done(function (a) {
	            			console.log("Producto: "+row.name+" agregado");
		            	})

	            		
	            	})
	            	.catch(err => {
	            		console.log(err)
	            	})

	            })
	        });
	    };
	    
	    // Tell JS To Start Reading The File.. You could delay this if desired
	    reader.readAsBinaryString(oFile);
	}
</script>