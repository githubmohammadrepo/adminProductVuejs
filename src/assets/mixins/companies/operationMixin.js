// mixins.js
export const mixinState = () => {
    return {
        fields: []
    };
}
export const mixinMutations = () => {
    return {
        addField(state, field) {
            state.fields.push(field)
        }
    };
}

export const mixinActions = () => {
    return {
        saveUpdateOneCompany({
            commit,
            dispatch,
            getters,
            rootGetters,
            rootState,
            state,
        }, field) {
            alert('update')
        },
        saveRemoveOneCompany({
            commit,
            dispatch,
            getters,
            rootGetters,
            rootState,
            state,
        }, field) {
            alert('remove')
        },
        saveAddOneCompany({
            commit,
            dispatch,
            getters,
            rootGetters,
            rootState,
            state,
        }, field) {
            alert('add')
        }
    };
}