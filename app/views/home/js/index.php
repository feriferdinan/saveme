<script>
    $(document).ready(function() {

        var loader = function() {
            setTimeout(function() {
                if ($('#ftco-loader').length > 0) {
                    $('#ftco-loader').removeClass('show');
                }
            }, 1);
        };
        loader();

        function showContent(app, data) {
            html = ``
            switch (app) {
                case 'facebook':
                case 'twitter':
                    html = ` <div class="row">
                <div class="col-md-6 ">
                  <h2 class="mb-1" >Video SD</h2>
                  <video controls="" loop=""  src="${data.data.videoSD}" height="160"> Maaf, tampaknya ada yang tidak beres </video>
                  <a download target"_blank" href="${data.data.videoSD}" class=" btn-outline-white px-5 py-3 ">Download Video</a>
                </div>
                <div class="col-md-6  ">
                  <h2 class="mb-1" >Video HD</h2>
                  <video controls="" loop=""  src="${data.data.videoHD}" height="160"> Maaf, tampaknya ada yang tidak beres </video>
                  <a download target"_blank" href="${data.data.videoHD}" class=" btn-outline-white px-5 py-3 ">Download Video</a>
                </div>
              </div>`
                    break;
                default:
                    if (data.type == "video") {
                        html = ` <div class="col-md-12  ">
                  <video controls="" loop=""  src="${data.data}" height="160"> Maaf, tampaknya ada yang tidak beres </video>
                  <a download target"_blank" href="${data.data}" class=" btn-outline-white px-5 py-3 ">Download Video</a>
                </div>`
                    } else {
                        html = `<a download target"_blank" href="${data.data}" class="btn btn-primary btn-outline-white px-5 py-3 ">Download Image</a>`
                    }
                    break;
            }
            $(".result-content").html(html)
        }

        function errorContent(message) {
            $(".error-content").html(`<h2 class="mb-1" >${message}</h2>`)
        }
        $(".js-download").click(function(e) {
            e.preventDefault()
            errorContent("")
            $(".result-content").html("")
            let app = $("#app").val()
            let url = $("#url").val()
            if (url.length == 0) {
                errorContent("Link is required")
                return
            }
            $(this).prop("disabled", true)
            $(this).html("Loading...")
            $.post(`<?= BASEURL ?>home/download`, {
                app,
                url
            }).done(resp => {
                $(this).prop("disabled", false)
                $(this).html("Download")
                let res = JSON.parse(resp)
                console.log(res);
                if (res.status) {
                    showContent(app, res)
                } else {
                    errorContent(res.message)
                }
            }).fail(err => {
                $(this).prop("disabled", false)
                $(this).html("Download")
                console.log(err);
                errorContent(err)
            })
        })
        $("#url").prop("placeholder", "https://www.instagram.com/p/CFQIBvdHeT")
        $("#app").change(function() {
            let val = $(this).val()
            let url = $("#url")
            switch (val) {
                case "instagram":
                    url.prop("placeholder", "https://www.instagram.com/p/CFQIBvdHeT")
                    break;
                case "facebook":
                    url.prop("placeholder", "https://web.facebook.com/watch/?v=267348670924203&extid=YJmpuBOBJgoP71dg")
                    break;
                case "twitter":
                    url.prop("placeholder", "https://twitter.com/i/status/1306767468971646977")
                    break;
                default:
                    url.prop("placeholder", "https://www.instagram.com/p/CFQIBvdHeT")
                    break;
            }
        })

    });
</script>