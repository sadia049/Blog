<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>All Posts</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-toggle="modal" data-target="#create-modal" class="float-end btn m-0 btn-sm bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>Author Name</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Excerpt</th>
                          
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tbody id="tableList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    getList();

    async function getList() {

        let tableData = $('#tableData');
        let tableList = $('#tableList');

        showLoader();

        let res = await axios.get('/list-post');

        hideLoader();

        

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach(function(item, index) {
            //console.log(item);
            let row = `<tr> 
             <td>${item['user']['name']}</td>
            <td>${(item['image'])!=null?`<img class="w-15 h-auto" alt="" src="${item['image']}">`:"No Image Available"}</td>
            
             <td>${item['title']}</td>
             <td>${item['excerpt']}</td>
             
             <td>
              <button data-path=${item['img_url']} data-id=${item['id']} class="btn editBtn btn-sm btn-outline-success">Edit</button>
              <button data-path=${item['img_url']} data-id=${item['id']} class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>

              </td>
  
         </tr>`

            tableList.append(row);


        });

       

        new DataTable('#tableData', {
            order: [
                [0, 'desc']
            ],
            lengthMenu: [5, 10, 15, 20, 30]
        });



    }
</script>
