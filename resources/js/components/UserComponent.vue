<template>
    <div id="user">
        <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <h4 class="card-title">User Info List</h4>
                <div class="card-tools" style="position: absolute; right: 1rem; top: 0.5rem;">
                    <button type="button" class="btn btn-primary"><i class="fa fa-plus" @click="create"></i></button>
                </div>
                </div>

                <div class="card-body">
                    <div class="col-md-6 mar">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Search:</strong>
                            </div>
                            <div class="col-md-3">
                                <select v-model="queryfield" class="form-control" id="fields">
                                    <option value="name">Name</option>
                                    <option value="email">Email</option>
                                    <option value="phone">Phone</option>
                                    <option value="Country">Country</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div class="table-resposive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-show="users.length" v-for="(item, index) in users" :key="item.id">
                                    <td scope="row">{{index+1}}</td>
                                    <td>{{item.name}}</td>
                                    <td>{{item.email}}</td>
                                    <td>{{item.phone}}</td>
                                    <td>{{item.Country}}</td>
                                    <td v-if="item.status == 1">
                                        <span class="">Active</span>
                                    </td>
                                    <td v-else>
                                        <span class="">Inactive</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                        <a @click="edit(item)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr v-show="!users.length">
                                    <td colspan="7">
                                        <div class="alert alert-danger">
                                            Sorry! No Data Found
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination-component 
                        v-if="pagination.last_page > 1 "
                        :pagination = "pagination"
                        :offset = "5"
                        @paginate ="query=== '' ? getData() : searchData()"
                        >                          
                       </pagination-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- User Profile modal start-->
    <!-- Modal -->
    <div class="modal fade" id="userAdd" tabindex="-1" role="dialog" aria-labelledby="userModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLongTitle">{{editMode ? "Edit" : "Add New"}} User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <alert-error :form="form" message="There were some problems with your input."></alert-error>
          <div class="modal-body">
                <div class="form-group">
                  <label>Name</label>
                  <input v-model="form.name" type="text" name="name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input v-model="form.email" type="email" name="email"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                  <label>Phone</label>
                  <input v-model="form.phone" type="tel" name="phone"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                  <has-error :form="form" field="phone"></has-error>
                </div>
                <div class="form-group">
                  <label>Country</label>
                  <input v-model="form.Country" type="text" name="Country"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('Country') }">
                  <has-error :form="form" field="Country"></has-error>
                </div>
                <div class="form-group">
                  <label>Address</label>
                  <input v-model="form.address" type="text" name="address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                  <has-error :form="form" field="address"></has-error>
                </div>
                <div class="form-check">
                  <label class="form-check-label">Status</label>
                  <input v-model="form.status" type="checkbox" name="status"
                    class="form-check-inputs" :class="{ 'is-invalid': form.errors.has('status') }">
                  <has-error :form="form" field="status"></has-error>
                </div>        
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" :disabled="form.busy" type="submit">Save</button>
          </div>
      </form>
        </div>
      </div>
    </div>
    <!-- End Modal-->
    <vue-progress-bar></vue-progress-bar>
    <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editMode: false,
                // Create a new form instance
                form: new Form({
                 id: '',
                 name: '',
                 email: '',
                 phone: '',
                 Country: '',
                 address: '',
                 status: ''
                }),
                query:'',
                queryfield: 'name',
                users: [],
                pagination:{
                    current_page: 1
                }
            }
        },
        watch:{
            query:function(newq, oldq){
                if(newq === ''){
                    this.getData()
                }else{
                    this.searchData();
                }
            }
        },
        mounted() {
            //console.log('Component mounted.')
            this.getData();
        },
        methods:{
            getData(){
                this.$Progress.start()
                axios.get('/api/user-profile?page='+this.pagination.current_page)
                .then(response =>{
                    this.users = response.data.data
                    this.pagination = response.data.meta
                    this.$Progress.finish()
                })
                .catch(e =>{
                    console.log(e)
                    this.$Progress.fail()
                })
            },
            searchData(){
                this.$Progress.start()
                axios
                    .get(
                      "/api/search/user-profile/" +
                        this.queryfield +
                        "/" +
                        this.query +
                        "?page=" +
                        this.pagination.current_page
                    )
                    .then(response =>{
                    this.users = response.data.data
                    this.pagination = response.data.meta
                    this.$Progress.finish()
                })
                .catch(e =>{
                    console.log(e)
                    this.$Progress.fail()
                })
            },
            create() {
              this.editMode = false;
              this.form.reset();
              this.form.clear();
              $("#userAdd").modal("show");
            },
            store(){
                this.$Progress.start()
                this.form.busy = true
                this.form.post('/api/user-profile')
                    .then(response =>{
                        this.getData()
                        $("#userAdd").modal("hide");
                        if(this.form.successful){
                            this.$Progress.finish()
                            this.$snotify.success('Data added successfully', 'Success')
                        }else{
                            this.$Progress.fail()
                            this.$snotify.success('Something went wrong', 'Error')
                        }
                    })
                    .catch(e =>{
                        this.$Progress.fail()
                        console.log(e)
                    })
            },
            edit(item){
                this.editMode = true
                this.form.reset();
                this.form.clear();
                this.form.fill(item);
                $("#userAdd").modal("show");
            },
            update(){
                this.$Progress.start()
                this.form.busy = true
                this.form.put('/api/user-profile/' +this.form.id)
                    .then(response =>{
                        this.getData()
                        $("#userAdd").modal("hide");
                        if(this.form.successful){
                            this.$Progress.finish()
                            this.$snotify.success('Data added successfully', 'Success')
                        }else{
                            this.$Progress.fail()
                            this.$snotify.success('Something went wrong', 'Error')
                        }
                    })
                    .catch(e =>{
                        this.$Progress.fail()
                        console.log(e)
                    })
            }
        }
    }
</script>
