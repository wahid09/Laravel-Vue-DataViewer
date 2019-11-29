<template>
    <div id="user">
        <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">User List</div>

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
                                        <a href="" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
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
    <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
    export default {
        data(){
            return{
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
            }
        }
    }
</script>
