import { Button } from "primereact/button";
import React from "react";

export function productCard(data, user_id) {
    async function addToCart(productId) {
        await fetch(`/cart`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                user_id: user_id,
                product_id: productId
            })
        }).then(res => {
            if(res.ok) {
                //window.location.href = "/cart";
            }
            return res.json();
        })
            .then(data => {
                console.log(data)
            })
    }

    return (
        <div style={{
            display: "flex",
            gap: "1rem",
            alignItems: "center",
            marginBottom: "1rem",
            marginTop: "1rem"
        }}>
            <img style={{ maxHeight: "12rem" }} src={`${data.imageSrc}`} alt={data.name}/>
            <div style={{
                display: "flex",
                flexDirection: "column",
                gap: "1rem"
            }}>
                <div>{data.name}</div>
                <div>
                    Cena: ${data.price}
                </div>
                <Button onClick={() => addToCart(data.id)}>Dodaj do koszyka</Button>
            </div>
        </div>
    )
}