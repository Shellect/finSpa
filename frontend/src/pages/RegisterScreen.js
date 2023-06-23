import React from "react";
import {RegistrationForm} from "../components/RegistrationForm";
import {useSelector} from "react-redux";
import Spinner from "../components/Spinner";

export default function RegisterScreen() {
    const { loading, isLoggedIn, error } = useSelector(
        (state) => state.user
    );
    return (
        <div className="container">
            <div className="row">
                <div className="col-12 col-md-4 offset-md-4">
                    {loading ? (<Spinner />) : (<RegistrationForm />)}
                </div>
            </div>
        </div>
    );
}