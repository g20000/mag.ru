// Shorthand log function
window.log = function() {
    window.console && window.console.log && window.console.log.apply(window.console, arguments);
};


(function($) {

	$(function() {

		var $clearBtn = $('.clear-btn');
		var $sendBtn = $('.send-btn');


		// create instance fields
		var damnUploaderInstance = {};
		
		$('.pkg-item').each(function(){
			
			var itemForm = $(this).find('.upload-form')[0];
			var pkgid = 'item'+$(itemForm).data('form-id');
			damnUploaderInstance[pkgid] = {};
			damnUploaderInstance[pkgid].DOM = $(this)[0];
			damnUploaderInstance[pkgid].fileInputDOM = $(this).find('.file-input')[0];
			damnUploaderInstance[pkgid].uploadFormDOM = $(this).find('.upload-form')[0];
			damnUploaderInstance[pkgid].uploadRowsDOM = $(this).find('.upload-panels')[0];
			damnUploaderInstance[pkgid].autostartOn = false;
			damnUploaderInstance[pkgid].previewsOn = true;
			damnUploaderInstance[pkgid].formId = $(this).data('form-id');
			damnUploaderInstance[pkgid].serverLogic = $(damnUploaderInstance[pkgid].uploadFormDOM).data('file-api-url');
			damnUploaderInstance[pkgid].dropBox = $(this).find('.drop-box')[0];

			damnUploaderInstance[pkgid].du = $(damnUploaderInstance[pkgid].fileInputDOM);
			damnUploaderInstance[pkgid].du.damnUploader({
				// URL of server-side upload handler
				url: damnUploaderInstance[pkgid].serverLogic,
				// File POST field name (for ex., it will be used as key in $_FILES array, if you using PHP)
				fieldName:  'my-file',
				// Limiting queued files count (if not defined [or false] - queue will be unlimited)
				limit: false,
				// Expected response type ('text' or 'json')
				dataType: 'json'
			});

			///// Setting up events handlers
			// Uploader events
			damnUploaderInstance[pkgid].du.on({
				'du.add' : fileAddHandler,
	
				'du.limit' : function() {
					log("File upload limit exceeded!");
				},
	
				'du.completed' : function() {
					log('******');
					log("All uploads completed!");
					alert('All uploads completed!');
					//document.location.reload();
				}
			});

		});
		//log(damnUploaderInstance);

		
		// Form submit
		$('.send-btn').bind('click', function(e) {
			// Sending files by HTML5 File API if available, else - form will be submitted on fallback handler
			if ($.support.fileSending) {
				var instance_id = 'item'+$(this).data('pkgid');
				var pkg_id = $(this).data('pkgid');
				// смотрим очередь в каждом
				var queue = damnUploaderInstance[instance_id].du.duGetQueue();
					for (z in queue) {
						// итерируемся по загружаемым объектам и добавляем им пост
						queue[z].addPostData('pkg_id',pkg_id);
					}
				// на отправку
				damnUploaderInstance[instance_id].du.duStart();
			}
		});
		


		// Clear button
		$clearBtn.on('click', function() {
			var pkg_id = $(this).data('pkgid');
			damnUploaderInstance[pkg_id].du.duCancelAll();
			$(damnUploaderInstance[pkg_id].uploadRowsDOM).empty();
			log('******');
			log("Uploads canceled :(");
		});

		///// Misc funcs
		var isImgFile = function(file) {
			return file.type.match(/image.*/);
		};


       // Creates queue table row with file information and upload status
        var createRowFromUploadItem = function(ui,pkg_id) {
            var $row = $('<div/>').css({'margin':'5px','display':'block'}).addClass('pull-left').prependTo($(damnUploaderInstance['item'+pkg_id].uploadRowsDOM));
            var $progressBar = $('<div/>').addClass('progress-bar').css({'width': '0%'});
            var $pbWrapper = $('<div/>').addClass('progress').css({'margin': 0,'height':'2em'}).append($progressBar);

            // Defining cancel button & its handler
            var $cancelBtn = $('<a/>').attr('href', 'javascript:').append(
                $('<span/>').addClass('fa fa-times')
            ).on('click', function() {
                var $statusCell =  $pbWrapper.parent();
				if ($statusCell.parent().data('img_id')!=undefined) {
					console.log('DELETE IMAGE '+$statusCell.parent().data('img_id'));
					$.ajax({
						url: $statusCell.parent().data('rmv_url'),
						data: { img_id: $statusCell.parent().data('img_id'), },
						dataType: 'JSON',
						success: function(data) { console.log(data); },
						error: function(v1,v2,v3) { console.log(v1,v2,v3); },
					});
				} else {
					$statusCell.parent().remove();
				}
                ui.cancel();
                log((ui.file.name || "[custom-data]") + " canceled");
            });
            
            // Generating preview
            var $preview;
            
			if (isImgFile(ui.file)) {
				// image preview (note: might work slow with large images)
				$preview = $('<img/>').css({'width': 80, 'height': 80}).css({'margin':'0 0 10px 0'});
				ui.readAs('DataURL', function(e) {
					$preview.attr('src', e.target.result);
				});
			} else {
				// plain text preview
				$preview = $('<i/>');
				ui.readAs('Text', function(e) {
					$preview.text(e.target.result.substr(0, 15) + '...');
				});
			}
            

            // Appending cells to row
            var $item = $('<div/>').addClass('well').append($preview).appendTo($row); // Preview
            $('<div/>').append($pbWrapper).appendTo($item); // Status
            $('<div/>').addClass('text-center').append($cancelBtn).appendTo($item); // Cancel button
            return $progressBar;
        };

		// File adding handler
		function fileAddHandler(e) {
			
			// e.uploadItem represents uploader task as special object,
			// that allows us to define complete & progress callbacks as well as some another parameters
			// for every single upload
			var ui = e.uploadItem;
			var filename = ui.file.name || ""; // Filename property may be absent when adding custom data

			// We can call preventDefault() method of event to cancel adding
			if (!isImgFile(ui.file)) {
				log(filename + ": is not image. Only images files accepted!");
				e.preventDefault();
				return ;
			}

			// We can replace original filename if needed
			if (!filename.length) {
				ui.replaceName = "custom-data";
			} else if (filename.length > 14) {
				ui.replaceName = filename.substr(0, 10) + "_" + filename.substr(filename.lastIndexOf('.'));
			}
			
			var uploadForm = $(damnUploaderInstance['item'+e.target.dataset.pkgid].uploadFormDOM);
			// We can add some data to POST in upload request
			ui.addPostData($(uploadForm).serializeArray()); // from array
			ui.addPostData('original-filename', filename); // .. or as field/value pair

			// Show info and response when upload completed
			var $progressBar = createRowFromUploadItem(ui, e.target.dataset.pkgid);
			log($progressBar);
			
			//log(ui);
			
			ui.completeCallback = function(success, data, errorCode) {
				log('******');
				log((this.file.name || "[custom-data]") + " completed");
				if (success) {
					log('recieved data:', data);
				} else {
					log('uploading failed. Response code is:', errorCode);
				}
			};

			// Updating progress bar value in progress callback
			ui.progressCallback = function(percent) {
				$progressBar.css('width', Math.round(percent) + '%');
				if (Math.round(percent) >= 100) {
						//$progressBar.html('Загружено!');
				}
			};

			// To start uploading immediately as soon as added
			damnUploaderInstance['item'+e.target.dataset.pkgid].autostartOn && ui.upload();
		};




	});

})(window.jQuery);


// File API support info
if(!$.support.fileSelecting) {
	log("[-] Your browser doesn't support File API (uploads may be performed only by default form submitting)");
} else {
	log("[√] Your browser supports multiple file selecting" + ($.support.fileSending ? " and sending" : ""));
	if(!$.support.fileReading) {
		log("[-] Your browser doesn't support file reading on client side");
	}
	if(!$.support.uploadControl) {
		log("[-] Your browser can't retrieve upload progress information (progress bars will be disabled)");
	}
	if(!$.support.fileSending) {
		log("[-] Your browser doesn't support FormData object (files will be send by default form submitting)");
	}
	log("Now select some files to see what happen ...");
}




 