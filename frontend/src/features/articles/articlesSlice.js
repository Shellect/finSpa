import {createSlice} from "@reduxjs/toolkit";
import getArticles from "../../actions/getArticles";

const initialState = {
    loading: false,
    articles: []
}

const articlesSlice = createSlice({
    name: "articles",
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(getArticles.pending, (state) => {
                state.loading = true;
            })
            .addCase(getArticles.fulfilled, (state, {payload}) => {
                state.loading = false;
                state.articles = payload;
            });
    }
});

const {reducer} = articlesSlice;
export default reducer;
