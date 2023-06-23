import React, {useState} from "react";
import {useSelector} from "react-redux";
import {AuthorisationForm} from "../components/AuthorisationForm";
import Spinner from "../components/Spinner";


export function LoginScreen() {
    const { loading, isLoggedIn, error } = useSelector(
        (state) => state.user
    );
    return (
        <div className="container">
            <div className="row">
                <div className="col-12 col-md-4 offset-md-4">
                    {loading ? (<Spinner />) : (<AuthorisationForm />)}
                </div>
            </div>
        </div>
    );
}
