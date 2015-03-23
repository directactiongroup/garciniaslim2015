				<div style="text-align:center; margin:0 auto 10px auto;">
					<img src="../images/secure.jpg" />
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<!-- home post-form content -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/moment.min.js"></script>
<script>
$(document).ready(function() {
	$("#radioTwo").live("click", function(){
		$("#customerInfo").slideDown();
	});
	$("#radioOne").live("click", function(){
		$("#customerInfo").slideUp();
	});
});
</script>
<script>
	$(document).ready(function() {
		$( "#alert" ).slideDown(1800).delay(15000);
		var tomorrow = (moment().add('days', 1).format('LL'));
		$("#shipdate #date").html(tomorrow);
		// Toggle product for Trial
		toggleProduct('<? echo $arrProductsKeys[0]; ?>');
	});
</script>	