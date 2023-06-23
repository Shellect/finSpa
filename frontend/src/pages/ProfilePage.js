import React from "react";
import {useSelector} from "react-redux";
import {Navigate} from "react-router-dom";

export function ProfilePage(){
    const user = useSelector(state => state.user);

    if (!user.isLoggedIn) {
        return <Navigate to="/signin" />;
    }

    return (
        <div className="container text-white">
            <header className="text-center">
                <h3>{user.username}'s Profile</h3>
            </header>

            <strong>Authorities:</strong>
            <ul>
                {user.roles && user.roles.map((role, index) => <li key={index}>{role}</li>)}
            </ul>
        </div>
    );
}