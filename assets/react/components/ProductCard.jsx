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
                <Button>Dodaj do koszyka</Button>
            </div>
        </div>
    )
}