import React from "react";
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import {RegistrationForm} from "./registrationForm";
import {AuthorisationForm} from "./authorisationForm";

export function App() {
    return (
        <div className="container">
            <div className="row">
                <div className="col-12 col-md-6 offset-md-3">
                    <BrowserRouter>
                        <Routes>
                            <Route path="*" element={<RegistrationForm/>} />
                            <Route path="/registration" element={<RegistrationForm/>} />
                            <Route path="/authorisation" element={<AuthorisationForm />} />
                        </Routes>
                    </BrowserRouter>

                </div>
            </div>
        </div>
    );
}