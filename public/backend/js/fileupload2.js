
        //I added event handler for the file upload control to access the files properties.
        document.addEventListener("DOMContentLoaded", init, false);
        //To save an array of attachments
        var AttachmentArray = [];
        //counter for attachment array
        var arrCounter = 0;
        //to make sure the error message for number of files will be shown only one time.
        var filesCounterAlertStatus = false;
        //un ordered list to keep attachments thumbnails
        var ul = document.createElement('ul');
        ul.className = ("thumb-Images");
        ul.id = "imgList";

        function init() {
            //add javascript handlers for the file upload event
            document.querySelector('#files2').addEventListener('change', handleFileSelect2, false);
        }
        //the handler for file upload event
        function handleFileSelect2(e) {
            //to make sure the user select file/files
            if (!e.target.files) return;
            //To obtaine a File reference
            var files = e.target.files;
            // Loop through the FileList2 and then to render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {
                //instantiate a FileReader object to read its contents into memory
                var fileReader = new FileReader();
                // Closure to capture the file information and apply validation.
                fileReader.onload = (function (readerEvt) {
                    return function (e) {

                        //Apply the validation rules for attachments upload
                        ApplyFileValidationRules2(readerEvt);

                        //Render attachments thumbnails.
                        RenderThumbnail2(e, readerEvt);

                        //Fill the array of attachment
                        FillAttachmentArray2(e, readerEvt)
                    };
                })(f);

                // Read in the image file as a data URL.
                // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.
                // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme
                fileReader.readAsDataURL(f);
            }
            document.getElementById('files2').addEventListener('change', handleFileSelect2, false);
        }

        //To remove attachment once user click on x button
        jQuery(function ($) {
            $('div').on('click', '.img-wrap .close', function () {
                var id = $(this).closest('.img-wrap').find('img').data('id');

                //to remove the deleted item from array
                var elementPos = AttachmentArray.map(function (x) { return x.FileName; }).indexOf(id);
                if (elementPos !== -1) {
                    AttachmentArray.splice(elementPos, 1);
                }

                //to remove image tag
                $(this).parent().find('img').not().remove();

                //to remove div tag that contain the image
                $(this).parent().find('div').not().remove();

                //to remove div tag that contain caption name
                $(this).parent().parent().find('div').not().remove();

                //to remove li tag
                var lis = document.querySelectorAll('#imgList li');
                for (var i = 0; li = lis[i]; i++) {
                    if (li.innerHTML == "") {
                        li.parentNode.removeChild(li);
                    }
                }

            });
        }
        )



        //Render attachments thumbnails.
        function RenderThumbnail2(e, readerEvt)
        {
            var li = document.createElement('li');
            ul.appendChild(li);
            li.innerHTML = ['<div class="img-wrap"> <span class="close">&times;</span>' +
                '<img class="thumb" src="', e.target.result, '" title="', escape(readerEvt.name), '" data-id="',
                readerEvt.name, '"/>' + '</div>'].join('');

            var div = document.createElement('div');
            div.className = "FileNameCaptionStyle";
            li.appendChild(div);
            div.innerHTML = [readerEvt.name].join('');
            document.getElementById('Filelist2').insertBefore(ul, null);
        }

        //Fill the array of attachment
        function FillAttachmentArray2(e, readerEvt)
        {
            AttachmentArray[arrCounter] =
            {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: readerEvt.name,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: readerEvt.type,
                Content: e.target.result.split("base64,")[1],
                FileSizeInBytes: readerEvt.size,
            };
            arrCounter = arrCounter + 1;
        }

         //To check files count according to upload conditions
        function CheckFilesCount(AttachmentArray)
        {
            //Since AttachmentArray.length return the next available index in the array,
            //I have used the loop to get the real length
            var len = 0;
            for (var i = 0; i < AttachmentArray.length; i++) {
                if (AttachmentArray[i] !== undefined) {
                    len++;
                }
            }
            //To check the length does not exceed 10 files maximum
            if (len > 1) {
                return false;
            }
            else
            {
                return true;
            }
        }

        //Apply the validation rules for attachments upload
        function ApplyFileValidationRules2(readerEvt)
        {
          //To check files count according to upload conditions
          if (CheckFilesCount(AttachmentArray) == false) {
            if (!filesCounterAlertStatus) {
                filesCounterAlertStatus = true;
                alert("You have added more than 10 files. According to upload conditions you can upload 10 files maximum");
            }
            e.preventDefault();
            return;
          }
        }

