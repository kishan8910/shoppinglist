<template>
    <div class="col-sm-12">
        <div v-if="loading">loading ...</div>
        <div class="row mb-1">
            <div class="col-md-12"> 
                <form v-on:submit.prevent="createItem()" accept-charset="UTF-8" method="get">
                    <div class="input-group"> 
                        <input type="text" v-model="item.name" placeholder="Add an item name" v-bind:id="item" v-bind:name="item"  class="form-control">
                        
                        <span class="input-group-btn">
                            <input type="submit"  class="btn btn-info ml-1" value="Save">
                        </span>
                    </div>
                    <span class="text-danger" role="alert">
                        <strong>{{ errors.get('name') }}</strong>
                    </span>
                </form>
            </div>
        </div>
        <!-- draggable component used for sorting the elements -->
        <draggable :list="items" :options="{animation:200}" :element="'div'" @change="saveSortItems(items)">
            <div class="" v-for="item in sortItems(items)" v-bind:key="item.id">
                <div class="card-body card mb-1" >
                    <div style="position: relative">
                        <i @click="deleteItem(item.id)" class="fa fa-trash delete-button"></i>
                    </div>
                    <div class="big-checkbox">
                        <input :value="item.id" class="form-check-input ml-1" type="checkbox"  v-bind:id="'item-check'+item.id" v-model="itemsBought"  @change="boughtItem(item.id,$event)">
                    </div>
                    <div class="ml-5">
                        <h4 v-if="itemsBought.includes(item.id)"><strike>{{ item.name }}</strike></h4>
                        <h4 v-else >{{ item.name }}</h4>
                    </div> 
                </div>
            </div>
        </draggable>
    </div>
</template>

<script>
/**
 * this class is ussed for catching validation errors and displaying error message
 */
class Errors {
    constructor() {
        this.errors = {};
    }

    get(field) {
        if( this.errors[field]) {
            return this.errors[field][0];
        }
    }

    record(errors) {
        this.errors = errors.errors;
    }
}

// for drag and drop feature
import draggable from 'vuedraggable';

export default {

    components : {
        draggable
    },
    
    data() {
        return {
            loading : false,
            errors : new Errors(),
            items : [],
            item : {
                id : '',
                name : '',
                price : '',
                sort : '',
                bought : ''
            },
            itemsBought: [],
        }
    },

    created() {
        this.fetchItems();
    },

    methods: {

        //method for fetch items
        fetchItems() {
            this.loading = true;
            axios({
                method: 'get',
                url: '/api/items/'
            })
            .then(response => {
                this.items = response.data.data;
                this.items.forEach(item => {
                    if(item.bought == 1) {
                        this.boughtItems.push(item.id);
                    }
                });
                this.loading = false;
            })
            .catch(error =>{
                console.log(error)
            });
        },

       
        
        /**
         * method for deleting an item
         * @param item_id
         */
        deleteItem(item_id) {
            
            if(confirm("Do you really want to delete?")) {
                axios({
                        method: 'delete',
                        url: '/api/items/'+item_id,
                    })
                    .then(res => {
                        this.fetchItems();
                        this.$emit("item-deleted");
                        Vue.use(VueToast);
                        let instance = Vue.$toast;
                        instance.success('Item deleted!', {
                            position: 'top-right'
                        });
                    })
                    .catch(error =>{
                        console.log(error)
                });
            }
        },
        
        /**
         * method for creating an item
         */
        createItem() {

            var maxOrder = Math.max.apply(Math, this.items.map(function(o) { return o.sort; }));
            axios({
                method: 'post',
                url: '/api/items',
                data: {
                    item_id : this.item.id,
                    name : this.item.name,
                    bought : 0,
                    sort : maxOrder + 1 
                }
            })
            .then(response => {
                this.errors = new Errors();
                this.items.push(item);                
                this.item = {};
                Vue.use(VueToast);
                let instance = Vue.$toast;
                instance.success('Item created!', {
                    position: 'top-right'
                });
            })
            .catch(error => {
                this.errors.record(error.response.data);
            });
        },
        /**
         * method for updating the bought status of the item
         * @param item_id
         * @param event
         */
        boughtItem(item_id, event) {
            
            if (event.target.checked) {
                var checked = 1;
            }
            else {
                var checked = 0;
            }

            axios({
                method: 'put',
                url: '/api/items/bought',
                data: {
                    item_id : item_id,
                    bought : checked
                }
            })
            .then(res => {
                Vue.use(VueToast);
                let instance = Vue.$toast;
                instance.success('Item updated!', {
                    position: 'top-right'
                });
            })
            .catch(error =>{
                this.errors.record(error.response.data);
            });
        }

    }
}
</script>
