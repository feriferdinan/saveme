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

        function showContent(data) {
            html = ``
            switch (data.host) {
                case 'www.facebook.com':
                case 'facebook.com':
                case 'twitter.com':
                case 'www.twitter.com':
                    html += ` <div class="row">`
                    if (data.data.videoSD.length !== 0) {
                        html += `<div class="${data.data.videoSD.length == 0 || data.data.videoHD.length == 0 ? 'col-md-12':'col-md-6'}">
                        <div class="card" style="width: 18rem;">
                                        <video style="height:10rem" class="card-img-top" controls="" loop="" src="${data.data.videoSD}" > Maaf, tampaknya ada yang tidak beres </video>
                                        <div class="card-body">
                                        <h5 class="mb-1 card-title">Video SD</h5>
                                        <a download target"_blank" href="${data.data.videoSD}" class="btn btn-primary ">Download Video</a>
                                        </div>
                                        </div>
                                    </div>`
                    }
                    if (data.data.videoHD.length !== 0) {
                        html += `<div class="${data.data.videoSD.length == 0 || data.data.videoHD.length == 0 ? 'col-md-12':'col-md-6'}">
                        <div class="card" style="width: 18rem;">
                                        <video style="height:10rem" class="card-img-top" controls="" loop="" src="${data.data.videoHD}" > Maaf, tampaknya ada yang tidak beres </video>
                                        <div class="card-body">
                                        <h5 class="mb-1 card-title">Video HD</h5>
                                        <a download target"_blank" href="${data.data.videoHD}" class="btn btn-primary ">Download Video</a>
                                        </div>
                                        </div>
                                    </div>`
                    }
                    html += `</div>`


                    break;
                default:
                    if (data.type == "video") {
                        html += `   <div class="card" style="width: 18rem;">
                                        <video style="height:10rem" class="card-img-top" controls="" loop="" src="${data.data}" > Maaf, tampaknya ada yang tidak beres </video>
                                        <div class="card-body">
                                        <a download target"_blank" href="${data.data}" class="btn btn-primary ">Download Video</a>
                                        </div>
                                    </div>`
                    } else {
                        html = `
                        <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="${data.data}" alt="Card image cap">
                                        <div class="card-body">
                                        <a download target"_blank" href="${data.data}" class="btn btn-primary ">Download Image</a>
                                        </div>
                                    </div>`

                    }
                    break;
            }
            $(".result-content").html(html)
        }

        function errorContent(message) {
            $(".error-content").html(`<h2 class="mb-1"> ${message} </h2>`)
        }
        $(".js-download").click(function(e) {
            e.preventDefault()
            $(".error-content").html(``)
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
                url
            }).done(resp => {
                $(this).prop("disabled", false)
                $(this).html("Download")
                let res = JSON.parse(resp)
                console.log(res);
                if (res.status) {
                    showContent(res)
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