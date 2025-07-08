$(function() {
	//tablesorter
	var pagerOptions = {
		container: $(".pager"),
		ajaxUrl: null,
		customAjaxUrl: function(table, url) { return url; },
		ajaxError: null,
		ajaxObject: { dataType: 'json' },
		ajaxProcessing: null,
		processAjaxOnInit: true,
		output: '{startRow:input} &ndash; {endRow} / {totalRows} rows',
		updateArrows: true,
		page: 0,
		size: 10,
		savePages : true,
		storageKey:'tablesorter-pager',
		pageReset: 0,
		fixedHeight: true,
		removeRows: false,
		countChildRows: false,
	};
	$("#tablesorter")
		.tablesorter({
			theme: 'blue',
			widthFixed: true,
			widgets: ['zebra', 'filter']
		})
		.bind('pagerChange pagerComplete pagerInitialized pageMoved', function(e, c) {
			var msg = '"</span> event triggered, ' + (e.type === 'pagerChange' ? 'going to' : 'now on') +
				' page <span class="typ">' + (c.page + 1) + '/' + c.totalPages + '</span>';
			$('#display')
				.append('<li><span class="str">"' + e.type + msg + '</li>')
				.find('li:first').remove();
		})
		.tablesorterPager(pagerOptions);
		$('#tablesorter').bind('pagerChange', function() {
			$('.toggle').text('Disable Pager');
		});
		$('.clear-pager-data').click(function() {
			$.tablesorter.storage( $('#tablesorter'), 'tablesorter-pager', '' );
		});
		$('.goto').click(function() {
			$('#tablesorter').trigger('pageAndSize', [1, 10]);
		});
});