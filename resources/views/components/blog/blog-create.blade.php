<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Create Category</h6>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                            
                            

                                <label class="form-label">Post Title</label>
                                <input type="text" class="form-control" id="postTitle" required>
                                <label>Post Content</label>
                                <textarea id="content" class="form-control" type="text" rows="20" cols="20" required></textarea>
                                <label class="form-label">Post Excerpt</label>
                                <input type="text" class="form-control" id="postExcerpt" required>
                                

                                <br/>
                                <img class="w-15" id="newImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>

                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file" class="form-control" id="postImg">

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn btn-sm  btn-success">Save</button>
                </div>
            </div>
    </div>
</div>


<script>



   


    async function Save() {

        let postTitle=document.getElementById('postTitle').value;
        let postContent = document.getElementById('content').value;
        let postExcerpt = document.getElementById('postExcerpt').value;
       
        let posttImg = document.getElementById('postImg').files[0];

        if (postTitle.length === 0) {
            errorToast("Title Required !")
        }
        else if(postContent.length===0){
            errorToast("Content Required !")
        }
        else if(postExcerpt.length===0){
            errorToast("Excerpt Required !")
        }
       

        else {

            document.getElementById('modal-close').click();

            let formData=new FormData();
            formData.append('img',posttImg)
            formData.append('title',postTitle)
            formData.append('content',postContent)
            formData.append('excerpt',postExcerpt)
            

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/create-post",formData,config)
            console.log(res.status)
            hideLoader();

            if(res.status===200){
                successToast(res.data['message']);
                document.getElementById("save-form").reset();
                document.getElementById('newImg').src=`{{asset('images/default.jpg')}}`
                await getList();
            }
            else{
                errorToast("Request fail !")
            }
        }
    }
</script>
