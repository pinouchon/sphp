setTimeout(function(){
	$('.progress .bar').each(function() {
		var me = $(this);
		var perc = me.attr("data-percentage");

		var current_perc = 0;

		var progress = setInterval(function() {
			if (current_perc>=perc) {
				clearInterval(progress);
			} else {
				current_perc +=1;
				me.css('width', (current_perc)+'%');
			}

			me.text((current_perc)+'%');

		}, 50);

	});

},300);