/*
 |-------------------------------------------------------------------------------
 | VUEX modules/file.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the file
 */

import filesAPI from "../api/file.js";

export const file = {
    /**
     * Defines the state being monitored for the module.
     */
    state: {
        files: [],
        filesLoadStatus: 0,
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {
        loadFiles({ commit }) {
            commit("setFilesLoadStatus", 1);

            filesAPI
                .getFiles()
                .then(function(response) {
                    commit("setFiles", response.data);
                    commit("setFilesLoadStatus", 2);
                })
                .catch(function() {
                    commit("setFiles", []);
                    commit("setFilesLoadStatus", 3);
                });
        },
    },

     /**
     * Defines the mutations used
     */
    mutations: {
        setFilesLoadStatus(state, status) {
            state.filesLoadStatus = status;
        },

        setFiles(state, files) {
            state.files = files;
        }
    },

    /**
     * Defines the getters used by the module
     */
    getters: {
        getFilesLoadStatus(state) {
            return state.filesLoadStatus;
        },

        getFiles(state) {
            return state.files;
        }
    }
};
