import React, {useEffect} from "react";
import {BrowserRouter, Routes, Route} from 'react-router-dom'
import {Header} from "./components/Header";
import {HomePage} from "./pages/HomePage";
import {ProfilePage} from "./pages/ProfilePage";
import {LoginScreen} from "./pages/LoginScreen";
import RegisterScreen from "./pages/RegisterScreen";
import BlogPage from "./pages/BlogPage";
import CreateArticle from "./pages/createArticle";

function BoardAdmin() {
    return null;
}

export function App() {
    return (
        <div>
            <BrowserRouter>
                <Header></Header>
                <Routes>
                    <Route exact path={"*"} element={<HomePage/>}/>
                    <Route exact path="/profile" element={<ProfilePage/>}/>

                    <Route path="/signup" element={<RegisterScreen/>}/>
                    <Route path="/signin" element={<LoginScreen/>}/>

                    <Route path="/blog" element={<BlogPage/>}/>
                    <Route path="/create-article" element={<CreateArticle/>}/>
                </Routes>
            </BrowserRouter>
        </div>
    );
}