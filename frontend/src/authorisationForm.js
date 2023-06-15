import React from "react";
import { Link, Outlet } from 'react-router-dom';

export function AuthorisationForm() {
    return (
        <div className="form_wrapper">
            <div className="form_container">
                <div className="title_container">
                    <h2>Authorisation</h2>
                </div>
                <div className="row clearfix">
                    <form>
                        <div className="input_field">
                            <span><i aria-hidden="true" className="fa fa-user"></i></span>
                            <input type="text" name="username" placeholder="Username" required/>
                        </div>
                        <div className="input_field"><span><i aria-hidden="true" className="fa fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Password" required/>
                        </div>
                        <input className="button" type="submit" value="Sign In"/>
                    </form>
                </div>
            </div>
            <p className="reg-link">No account? <Link to="/registration">Sign Up</Link></p>
            <Outlet />
        </div>
    );
}
