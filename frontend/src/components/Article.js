import React from "react";

export default function Article({title, content})
{
    return (
        <div className="card mt-3">
            <div className="card-header">
                <h5>{title}</h5>
            </div>
            <div className="card-body">
                <p>{content}</p>
            </div>
        </div>
    );
}