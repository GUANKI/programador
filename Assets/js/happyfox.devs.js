const instructorProgramator  = () => {
    let timeout;
    const selected = document.querySelector("#select-selected");
    const input = document.querySelector("#select-input");
    const list = document.querySelector("#select-list");
    const inputValue = document.querySelector("#select-value");

    let selectedArray = [];

    const showList = () => {
        list.style.setProperty("display", "block")
    }

    const purgeData = (e) => {
      list.innerHTML = "";
      list.style.removeProperty("display")
    };

    const renderSelected = () => {
        selected.innerHTML = "";

        selectedArray.forEach(d => {
            const p = document.createElement("p");

            p.setAttribute("data-instructor-id", d.id);
            p.innerHTML = d.nombre;

            const span = document.createElement("span");
            span.innerHTML = "x";

            p.appendChild(span)
            selected.appendChild(p);
            
            const removeItem = () => {
                selectedArray = selectedArray.filter(e => e.id !== d.id);
                span.removeEventListener("click", removeItem);
                renderSelected();
            }

            span.addEventListener("click", removeItem);
            inputValue.value = JSON.stringify(selectedArray);
        });

        const height = selected.clientHeight;

        input.style.setProperty("margin-top", `${height}px`);
    }

    input.style.setProperty("height", "24px")

    const fetchData = async (e) => {
        const result = await fetch(`http://localhost/programador/?c=instructor&a=buscarinstructor&search=${e.target.value}`);

        list.innerHTML = "";

        const data = await result.json();
        data.sort((a, b) => a.nombre.localeCompare(b.nombre));

        data.forEach(d => {
            if (selectedArray.find(e => e.id === d.id)) return;

            const p = document.createElement("p");

            p.setAttribute("data-instructor-id", d.id);
            p.innerHTML = d.nombre;

            list.appendChild(p);

            p.addEventListener("click", () => {
                selectedArray.push(d);
                purgeData();
                renderSelected();
            })
        });
        
        showList();
    };

    input.addEventListener("focus", fetchData);
    input.addEventListener("input", (e) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fetchData(e), 1000);
    });
}

const init = () => {
    instructorProgramator();
}

document.addEventListener("DOMContentLoaded", init);