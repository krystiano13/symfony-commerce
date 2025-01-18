import { Button } from "primereact/button";
import React from "react";

export function productCard(data) {
    return (
        <div style={{
            display: "flex",
            gap: "1rem",
            alignItems: "center",
            marginBottom: "1rem",
            marginTop: "1rem"
        }}>
            <img src={`https://primefaces.org/cdn/primereact/images/product/${data.image}`} alt={data.name}/>
            <div style={{
                display: "flex",
                flexDirection: "column",
                gap: "1rem"
            }}>
                <div>{data.name}</div>
                <div>
                    Cena: ${data.price}
                </div>
                <Button>Dodaj do koszyka</Button>
            </div>
        </div>
    )
}