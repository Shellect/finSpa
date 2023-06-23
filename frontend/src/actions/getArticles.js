import {createAsyncThunk} from "@reduxjs/toolkit";
import userService from "../services/data.service";

const       getArticles = createAsyncThunk(
    "articles/all",
    async (param, {rejectWithValue}) => {
        try {
            return await userService.getPublicContent();
        } catch (error) {
            return error.response?.data.message
                ? rejectWithValue(error.response.data.message)
                : rejectWithValue(error.message);
        }
    }
);

export default getArticles;