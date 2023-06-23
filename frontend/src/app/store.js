import {configureStore} from '@reduxjs/toolkit';
import userReducer from '../features/auth/userSlice';
import articlesReducer from "../features/articles/articlesSlice";

const store = configureStore({
    reducer: {
        user: userReducer,
        articles: articlesReducer
    },
    devTools: true
});

export default store;


