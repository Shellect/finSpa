import React, {useEffect, useState} from 'react';
import {icon} from "@fortawesome/fontawesome-svg-core/import.macro";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {Link} from "react-router-dom";
import {useSelector} from "react-redux";
import {useDispatch} from "react-redux";
import {logout} from "../features/auth/userSlice";


export function Header() {
    let {isLoggedIn, username} = useSelector(state => state.user);
    const dispatch = useDispatch();

    const handleLogout = () => dispatch(logout())

    useEffect(() => {
        if(localStorage.getItem("authToken")){
            isLoggedIn = true;
        }
    }, []);

    return (
        <header>
            <nav className="navbar navbar-expand-lg bg-body-yellow">
                <div className="container-fluid">
                    <a className="navbar-brand text-white" href="#">Fin SPA</a>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul className="navbar-nav align-content-center">

                            {isLoggedIn ? (
                                <>
                                    <li className="nav-item">
                                        <Link to="/blog" className="nav-link">Blog</Link>
                                    </li>
                                    <li className="nav-item">
                                        <Link to="/create-article" className="nav-link">Create new article</Link>
                                    </li>
                                    <li className="nav-item">
                                        <Link to="/profile" className="nav-link">
                                        <span className="avatar">
                                            <FontAwesomeIcon icon={icon({name: "user", style: "solid"})}/>
                                        </span>
                                            {username}
                                        </Link>
                                    </li>
                                    <li className="nav-item">
                                        <span className="nav-link" onClick={handleLogout}>Logout</span>
                                    </li>
                                </>
                            ) : (
                                <>
                                    <li className="nav-item">
                                        <Link to="/signin" className="nav-link">Login</Link>
                                    </li>
                                    <li className="nav-item">
                                        <Link to="/signup" className="nav-link">Register</Link>
                                    </li>
                                </>
                            )}
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    );
}